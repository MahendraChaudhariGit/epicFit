@extends('Result.profile_details')

@section('page-title')
    <span> Chronotype Survey </span> 
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
                        <form action="{{ url('/store-chronotype-survey') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                        <input id="website" name="website" type="text" value="">
                         <div id="middle-wizard">

                            <div class="step">
                                <h2 class="section_title"> Sleep Genie Chronotype Survey </h2>
                                <div class="form-group add_top_30">
                                    <p>instructions: <br>
                                        Read all questions carefully <br>
                                        Be honest and don`t go back  and second guess your answers <br>
                                        Your first response is usually the most accurate <br>
                                        Answer all questions 
                                    </p>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> What time would you get up if were entirely free to plan your day? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5:00 - 6:29 am
                                            <input type="radio" name="getup_score" id="getup_score5" value="5:00 - 6:29 am,5" data-value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 6:30 - 7:44 am
                                            <input type="radio" name="getup_score" id="getup_score4" value="6:30 - 7:44 am,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 7:45 - 9:44 am
                                            <input type="radio" name="getup_score" id="getup_score3" value="7:45 - 9:44 am,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 9:45 - 10:59 
                                            <input type="radio" name="getup_score" id="getup_score2" value="9:45 - 10:59 am,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 11:00 - 11:59 am
                                            <input type="radio" name="getup_score" id="getup_score1" value="11:00 - 11:59 am,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Midday - 5:00 pm
                                            <input type="radio" name="getup_score" id="getup_score0" value="Midday - 5:00 pm,0" data-value="0">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> What time would you go to bed if you were entirely free to plan your evening? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 8:00 - 8:59 pm
                                            <input type="radio" name="goto_bed_score" id="goto_bed_score5" value="8:00 - 8:59 pm,5" data-value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 9:00 - 10:14 pm
                                            <input type="radio" name="goto_bed_score" id="goto_bed_score4" value="9:00 - 10:14 pm,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 10:15 pm - 12:29 am
                                            <input type="radio" name="goto_bed_score" id="goto_bed_score3" value="10:15 pm - 12:29 am,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 12:30 - 1:44 am
                                            <input type="radio" name="goto_bed_score" id="goto_bed_score2" value="12:30 - 1:44 am,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 1:45 - 2:59 am
                                            <input type="radio" name="goto_bed_score" id="goto_bed_score1" value="1:45 - 2:59 am,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 3:00 - 8:00 am
                                            <input type="radio" name="goto_bed_score" id="goto_bed_score0" value="3:00 - 8:00 am,0" data-value="0">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> If there is a specific time at which you have to get up in the morning, to what extend to you depend on being woken up by an alarm clock? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Not at all dependent
                                            <input type="radio" name="specific_time_getup_score" id="specific_time_getup_score4" value="Not at all dependent,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Slightly dependent
                                            <input type="radio" name="specific_time_getup_score" id="specific_time_getup_score3" value="Slightly dependent,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly dependent
                                            <input type="radio" name="specific_time_getup_score" id="specific_time_getup_score2" value="Fairly dependent,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very dependent
                                            <input type="radio" name="specific_time_getup_score" id="specific_time_getup_score1" value="Very dependent,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> How easy do you find it to get up in the morning (when you are not woken up unexpectedly)? </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Not at all easy
                                            <input type="radio" name="easy_to_getup_score" id="easy_to_getup_score1" value="Not at all easy,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Not very easy
                                            <input type="radio" name="easy_to_getup_score" id="easy_to_getup_score2" value="Not very easy,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly easy
                                            <input type="radio" name="easy_to_getup_score" id="easy_to_getup_score3" value="Fairly easy,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very easy
                                            <input type="radio" name="easy_to_getup_score" id="easy_to_getup_score4" value="Very easy,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> How alert do you feel during the first half hour after you wake up in morning? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Not at all alert
                                            <input type="radio" name="first_half_hour_score" id="first_half_hour_score1" value="Not at all alert,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Slightly alert
                                            <input type="radio" name="first_half_hour_score" id="first_half_hour_score2" value="Slightly alert,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly alert
                                            <input type="radio" name="first_half_hour_score" id="first_half_hour_score3" value="Fairly alert,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very alert
                                            <input type="radio" name="first_half_hour_score" id="first_half_hour_score4" value="Very alert,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> How hungry do you feel during the first half hour after you wake up in morning? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Not at all hungry
                                            <input type="radio" name="first_half_hour_hungry_score" id="first_half_hour_hungry_score1" value="Not at all hungry,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Slightly hungry
                                            <input type="radio" name="first_half_hour_hungry_score" id="first_half_hour_hungry_score2" value="Slightly hungry,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly hungry
                                            <input type="radio" name="first_half_hour_hungry_score" id="first_half_hour_hungry_score3" value="Fairly hungry,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very hungry
                                            <input type="radio" name="first_half_hour_hungry_score" id="first_half_hour_hungry_score4" value="Very hungry,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> During the first-half hour after you wake up in morning, how tired do you feel? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very tired
                                            <input type="radio" name="first_half_hour_tired_score" id="first_half_hour_tired_score1" value="Very tired,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly tired
                                            <input type="radio" name="first_half_hour_tired_score" id="first_half_hour_tired_score2" value="Fairly tired,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly refreshed
                                            <input type="radio" name="first_half_hour_tired_score" id="first_half_hour_tired_score3" value="Fairly refreshed,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very refreshed
                                            <input type="radio" name="first_half_hour_tired_score" id="first_half_hour_tired_score4" value="Very refreshed,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> If you have no commitment the next day, what time would you go to bed compared to your usual bedtime?</h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Seldom or never later
                                            <input type="radio" name="no_commitment_goto_bed_score" id="no_commitment_goto_bed_score4" value="Seldom or never later,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Less then one hour later
                                            <input type="radio" name="no_commitment_goto_bed_score" id="no_commitment_goto_bed_score3" value="Less then one hour later,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 1-2 hours later
                                            <input type="radio" name="no_commitment_goto_bed_score" id="no_commitment_goto_bed_score2" value="1-2 hours later,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> More then two hours later
                                            <input type="radio" name="no_commitment_goto_bed_score" id="no_commitment_goto_bed_score1" value="More then two hours later,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> You have decided to engage in some physical exercsise. A friend suggests that you do this for one hour twice a week
                                    and the best time for him/her is between 7:00 - 8:00 am. Bearing in mind nothing but your own internal "clock",how do think you would perform? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would be in good form
                                            <input type="radio" name="engage_in_physical_exercise_score" id="engage_in_physical_exercise_score4" value="Would be in good form,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would be in reasonable form
                                            <input type="radio" name="engage_in_physical_exercise_score" id="engage_in_physical_exercise_score3" value="Would be in reasonable form,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would find it difficult
                                            <input type="radio" name="engage_in_physical_exercise_score" id="engage_in_physical_exercise_score2" value="Would find it difficult,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would find it very difficult
                                            <input type="radio" name="engage_in_physical_exercise_score" id="engage_in_physical_exercise_score1" value="Would find it very difficult,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> At what time of day do you feel you become tired as a result of need for sleep? </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 8:00 - 8:59 pm
                                            <input type="radio" name="feel_tired_in_day_score" id="feel_tired_in_day_score5" value="8:00 - 8:59 pm,5" data-value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 9:00 - 10:14 pm
                                            <input type="radio" name="feel_tired_in_day_score" id="feel_tired_in_day_score4" value="9:00 - 10:14 pm,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 10:15 pm - 12:44 am
                                            <input type="radio" name="feel_tired_in_day_score" id="feel_tired_in_day_score3" value="10:15 pm - 12:44 am,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 12:45 - 1:59 am
                                            <input type="radio" name="feel_tired_in_day_score" id="feel_tired_in_day_score2" value="12:45 - 1:59 am,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 2:00 - 3:00 am
                                            <input type="radio" name="feel_tired_in_day_score" id="feel_tired_in_day_score1" value="2:00 - 3:00 am,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> You want to be at your peak performance for a test that you know is going to be mentally exhausting
                                    and will last for two hours. You are entirely free to plan your day. Considering only your own  internal "clock",
                                    wich ONE of the four testing times would you choose? 
                                </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 8:00 - 10:00 am
                                            <input type="radio" name="peak_performance_score" id="peak_performance_score4" value="8:00 - 10:00 am,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 11:00 am - 1:00 pm
                                            <input type="radio" name="peak_performance_score" id="peak_performance_score3" value="11:00 am - 1:00 pm,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 3:00 - 5:00 pm
                                            <input type="radio" name="peak_performance_score" id="peak_performance_score2" value="3:00 - 5:00 pm,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 7:00 - 9:00 pm
                                            <input type="radio" name="peak_performance_score" id="peak_performance_score1" value="7:00 - 9:00 pm,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> If you got into bed at 11:00 pm,how tired would you be?
                                </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Not at all tired
                                            <input type="radio" name="got_into_bed_tired_score" id="got_into_bed_tired_score1" value="Not at all tired,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> A little tired
                                            <input type="radio" name="got_into_bed_tired_score" id="got_into_bed_tired_score2" value="A little tired,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Fairly tired
                                            <input type="radio" name="got_into_bed_tired_score" id="got_into_bed_tired_score3" value="Fairly tired,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Very tired
                                            <input type="radio" name="got_into_bed_tired_score" id="got_into_bed_tired_score4" value="Very tired,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> For some reason, you have gone to bed several hours later than usual, but there is no need to get up
                                    any perticular time the next morning. Which ONE of the following are you most likely to do?
                                </h2>
                                
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Will wake up at usual time, but will NOT fall back asleep
                                            <input type="radio" name="goto_several_hour_into_bed_score" id="goto_several_hour_into_bed_score4" value="Will wake up at usual time but will NOT fall back asleep,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Will wake up at usual time and will doze thereafter
                                            <input type="radio" name="goto_several_hour_into_bed_score" id="goto_several_hour_into_bed_score3" value="Will wake up at usual time and will doze thereafter,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Will wake up at usual time, but will fall asleep again
                                            <input type="radio" name="goto_several_hour_into_bed_score" id="goto_several_hour_into_bed_score2" value="Will wake up at usual time but will fall asleep again,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Will NOT wake up until later then usual
                                            <input type="radio" name="goto_several_hour_into_bed_score" id="goto_several_hour_into_bed_score1" value="Will NOT wake up until later then usual,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> One night you have to remain awake between 4:00 - 6:00 am in order to carry out a night watch. you 
                                    have no commitments the next day. Which one of the alternatives will suite you best?
                                </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2">Would NOT go to bed until watch was over
                                            <input type="radio" name="one_night_awake_score" id="one_night_awake_score1" value="Would NOT go to bed until watch was over,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would take a nap before and sleep after
                                            <input type="radio" name="one_night_awake_score" id="one_night_awake_score2" value="Would take a nap before and sleep after,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would take a good sleep before and nap after
                                            <input type="radio" name="one_night_awake_score" id="one_night_awake_score3" value="Would take a good sleep before and nap after,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would sleep only before watch
                                            <input type="radio" name="one_night_awake_score" id="one_night_awake_score4" value="Would sleep only before watch,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="step">
                                <h2 class="section_title"> 
                                    You have to do two hours of hard physical work. You aur entirely free to plan your day and considering
                                    only your own internal "clock" Which one of the following times would you choose? 
                                </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 8:00 - 10:00 am
                                            <input type="radio" name="two_hour_hard_work_score" id="two_hour_hard_work_score4" value="8:00 - 10:00 am,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 11:00 am - 1:00 pm
                                            <input type="radio" name="two_hour_hard_work_score" id="two_hour_hard_work_score3" value="11:00 am - 1:00 pm,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 3:00 - 5:00 pm
                                            <input type="radio" name="two_hour_hard_work_score" id="two_hour_hard_work_score2" value="3:00 - 5:00 pm,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 7:00 - 9:00 pm
                                            <input type="radio" name="two_hour_hard_work_score" id="two_hour_hard_work_score1" value="7:00 - 9:00 pm,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> 
                                    You have decided to engage in hard physical exercise. A friend suggests that you do this for one hour
                                    twice a week and the best time for him/her is between 10:00 - 11:00 pm. Bearing in mind nothing else but
                                    your own internal "clock", how well do you think you would perform?    
                                </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would be in good form
                                            <input type="radio" name="physical_exercise_score" id="physical_exercise_score4" value="Would be in good form,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would be in reasonable form
                                            <input type="radio" name="physical_exercise_score" id="physical_exercise_score3" value="Would be in reasonable form,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would find it difficult
                                            <input type="radio" name="physical_exercise_score" id="physical_exercise_score2" value="Would find it difficult,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Would find it very difficult
                                            <input type="radio" name="physical_exercise_score" id="physical_exercise_score1" value="Would find it very difficult,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> 
                                    Suppose that you can choose your school hours. Assume that you went to school for five hours per day
                                    and that school was interesting and enjoyable. Which five consecutive hours would you select?
                                </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5 hours starting between 4:00 - 7:59 am
                                            <input type="radio" name="school_hours_score" id="school_hours_score5" value="5 hours starting between 4:00 - 7:59 am,5" data-value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5 hours starting between 8:00 - 8:59 am
                                            <input type="radio" name="school_hours_score" id="school_hours_score4" value="5 hours starting between 8:00 - 8:59 am,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5 hours starting between 9:00 am - 1:59 pm
                                            <input type="radio" name="school_hours_score" id="school_hours_score3" value="5 hours starting between 9:00 am - 1:59 pm,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5 hours starting between 2:00 - 4:59 pm
                                            <input type="radio" name="school_hours_score" id="school_hours_score2" value="5 hours starting between 2:00 - 4:59 pm,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5 hours starting between 5:00 pm - 3:59 am
                                            <input type="radio" name="school_hours_score" id="school_hours_score1" value="5 hours starting between 5:00 pm - 3:59 am,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> 
                                    At what time of the day do you think that you reach your "feeling best" peak?
                                </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5:00 - 7:59 am
                                            <input type="radio" name="feeling_best_score" id="feeling_best_score5" value="5:00 - 7:59 am,5" data-value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 8:00 - 9:59 am
                                            <input type="radio" name="feeling_best_score" id="feeling_best_score4" value="8:00 - 9:59 am,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 10:00 am - 4:59 pm
                                            <input type="radio" name="feeling_best_score" id="feeling_best_score3" value="10:00 am - 4:59 pm,3" data-value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 5:00 - 9:59 pm
                                            <input type="radio" name="feeling_best_score" id="feeling_best_score2" value="5:00 - 9:59 pm,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> 10:00 pm - 4:59 am
                                            <input type="radio" name="feeling_best_score" id="feeling_best_score1" value="10:00 pm - 4:59 am,1" data-value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                               
                            </div>

                            <div class="step">
                                <h2 class="section_title"> 
                                    One hears about "morning" and "evening" types of people. Which ONE of these types do you consider yourself to be?
                                </h2>
                               
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Definitely a "morning" type
                                            <input type="radio" name="types_of_people_score" id="types_of_people_score6" value="Definitely a 'morning' type,6" data-value="6">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Rather more a "morning" type then an "evening" type
                                            <input type="radio" name="types_of_people_score" id="types_of_people_score4" value="Rather more a 'morning' type then an 'evening' type,4" data-value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Rather more an "evening" type then  "morning" type
                                            <input type="radio" name="types_of_people_score" id="types_of_people_score2" value="Rather more an 'evening' type then  'morning' type,2" data-value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Definitely an "evening" type
                                            <input type="radio" name="types_of_people_score" id="types_of_people_score0" value="Definitely an 'evening' type,0" data-value="0">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                
                            </div>

                            <div class="submit step" id="end">
                                <h2 class="section_title"> SCORING </h2>
                                <div class="form-group add_top_30">
                                    <label for=""> Total score</label>
                                    <input type="text" readonly class="form-control" name="total_score" id="total_score">
                                </div>
                                <div class="form-group add_top_30">
                                    <h2 class="section_title"> SCORING SUMMARY</h2>
                                    <P>Scores range from 16-86. <br>
                                        Scores of 16-30 are indicative of definite evening types <br>
                                        Scores of 31-41 are indicative of moderate evening types <br>
                                        Scores of 42-58 are indicative of intermediate types <br>
                                        Scores of 59-69 are indicative of moderate morning types <br>
                                        Scores of 70-86 are indicative of definite morning types <br>
                                        <br>
                                        What to do if your type effects your lifestyle, don`t worry, the good news is you can re-train your body
                                        clock to fit into your desired reqiuirements for bedtime and wake up times.
                                    </P>
                                </div>
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
        var score = $("#total_score").val();
        var prevSelect1 = '';
        var scorePrev1 = 0;
        $("input[name='getup_score']").click(function(){
            prevSelect1 = scorePrev1;
            scorePrev1 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect1);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect2 = '';
        var scorePrev2 = 0;
        $("input[name='goto_bed_score']").click(function(){
            prevSelect2 = scorePrev2;
            scorePrev2 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect2);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect3 = '';
        var scorePrev3 = 0;
        $("input[name='specific_time_getup_score']").click(function(){
            prevSelect3 = scorePrev3;
            scorePrev3 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect3);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect4 = '';
        var scorePrev4 = 0;
        $("input[name='easy_to_getup_score']").click(function(){
            prevSelect4 = scorePrev4;
            scorePrev4 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect4);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect5 = '';
        var scorePrev5 = 0;
        $("input[name='first_half_hour_score']").click(function(){
            prevSelect5 = scorePrev5;
            scorePrev5 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect5);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect6 = '';
        var scorePrev6 = 0;
        $("input[name='first_half_hour_hungry_score']").click(function(){
            prevSelect6 = scorePrev6;
            scorePrev6 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect6);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect7 = '';
        var scorePrev7 = 0;
        $("input[name='first_half_hour_tired_score']").click(function(){
            prevSelect7 = scorePrev7;
            scorePrev7 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect7);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect8 = '';
        var scorePrev8 = 0;
        $("input[name='no_commitment_goto_bed_score']").click(function(){
            prevSelect8 = scorePrev8;
            scorePrev8 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect8);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect9 = '';
        var scorePrev9 = 0;
        $("input[name='engage_in_physical_exercise_score']").click(function(){
            prevSelect9 = scorePrev9;
            scorePrev9 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect9);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect10 = '';
        var scorePrev10 = 0;
        $("input[name='feel_tired_in_day_score']").click(function(){
            prevSelect10 = scorePrev10;
            scorePrev10 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect10);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect11 = '';
        var scorePrev11 = 0;
        $("input[name='peak_performance_score']").click(function(){
            prevSelect11 = scorePrev11;
            scorePrev11 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect11);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect12 = '';
        var scorePrev12 = 0;
        $("input[name='got_into_bed_tired_score']").click(function(){
            prevSelect12 = scorePrev12;
            scorePrev12 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect12);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect13 = '';
        var scorePrev13 = 0;
        $("input[name='goto_several_hour_into_bed_score']").click(function(){
            prevSelect13 = scorePrev13;
            scorePrev13 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect13);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect14 = '';
        var scorePrev14 = 0;
        $("input[name='one_night_awake_score']").click(function(){
            prevSelect14 = scorePrev14;
            scorePrev14 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect14);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect15 = '';
        var scorePrev15 = 0;
        $("input[name='two_hour_hard_work_score']").click(function(){
            prevSelect15 = scorePrev15;
            scorePrev15 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect15);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect16 = '';
        var scorePrev16 = 0;
        $("input[name='physical_exercise_score']").click(function(){
            prevSelect16 = scorePrev16;
            scorePrev16 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect16);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect17 = '';
        var scorePrev17 = 0;
        $("input[name='school_hours_score']").click(function(){
            prevSelect17 = scorePrev17;
            scorePrev17 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect17);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect18 = '';
        var scorePrev18 = 0;
        $("input[name='feeling_best_score']").click(function(){
            prevSelect18 = scorePrev18;
            scorePrev18 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect18);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

        var prevSelect19 = '';
        var scorePrev19 = 0;
        $("input[name='types_of_people_score']").click(function(){
            prevSelect19 = scorePrev19;
            scorePrev19 = $(this).attr('data-value');
            score = Number(score)-Number(prevSelect19);
            score = Number(score)+Number($(this).attr('data-value'));
            $("#total_score").val(score);
        })

       

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


