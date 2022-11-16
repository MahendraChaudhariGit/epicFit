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
                        <form action="{{ url('sleep-questionnaire') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                        <input id="website" name="website" type="text" value="">
                         <div id="middle-wizard">
                            <div class="step">
                                <h2 class="section_title">Sleep Genie Questionnaire/Servey</h2>
                                <div class="row">
                                    
                                    <div class="col-md-6 form-group">
                                        <label for=""> First Name</label>
                                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{ $client->firstname }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Last Name</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{ $client->lastname }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" id="contact_number" value="{{ $client->phonenumber }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Best time to contact</label>
                                        <input type="text" class="form-control" name="contact_time" id="contact_time">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Email</label>
                                        <input type="text" class="form-control" name="email" id="email" value="{{ $client->email }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Gender</label>
                                        <input type="text" class="form-control" name="gender" id="gender" value="{{ $client->gender }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Age</label>
                                        <input type="number" class="form-control" name="age" id="age" value="{{ $age }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Date</label>
                                        <input type="date" class="form-control" name="date1" id="datepicker1">
                                    </div>
                                </div>
                               
                            </div>
                            <!-- /step-->
                                <div class="step">
                                    <h2 class="section_title">Demographic</h2>
                                    <div class="form-group add_top_30">
                                        <label class="">What ethnicity are you? </label>
                                        <input type="text" class="form-control" name="ethnicity" value="" id="ethnicity">
                                    </div>
                                    
                                </div>
                            
                                <div class="step">
                                    <h2 class="section_title">Present marital status?</h2>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Married
                                            <input type="radio" name="marital_status" id="marital_status" value="Married">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Single
                                            <input type="radio" name="marital_status" id="marital_status1" value="Single">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Defacto
                                            <input type="radio" name="marital_status" id="marital_status2" value="Defacto">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Civil Union
                                            <input type="radio" name="marital_status" id="marital_status3" value="Civil Union">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Divorced
                                            <input type="radio" name="marital_status" id="marital_status4" value="Divorced">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group add_top_30">
                                        <label class="container_radio version_2"> Widow/Widower
                                            <input type="radio" name="marital_status" id="marital_status5" value="Widow/Widower">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                             <!-- /step-->
                            
                            <div class="step">
                                <h2 class="">Current Sleep Situation</h2>
                                <h2 class="section_title">Are you getting enough sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="enough_sleep" id="enough_sleep" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="enough_sleep" id="enough_sleep1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="step">
                                <h2 class="section_title">Are you getting quality sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="quality_sleep" id="quality_sleep" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="quality_sleep" id="quality_sleep1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title">Does your quality or quantity of sleep affect your functioning and duties? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="sleep_affect" id="sleep_affect" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="sleep_affect" id="sleep_affect1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title">Do you need to wake up early and does this affect your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="wakeup_early" id="wakeup_early" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="wakeup_early" id="wakeup_early1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title">Do you need to do chores and planning at night which affects the time you go to bed? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="planning_at_night" id="planning_at_night" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="planning_at_night" id="planning_at_night1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title">Do you have an injury that is affecting your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="have_an_injury" id="have_an_injury" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="have_an_injury" id="have_an_injury1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title">Do you have a partner who is affecting your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="have_a_partner" id="have_a_partner" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="have_a_partner" id="have_a_partner1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> Do you have a child or baby who is affecting your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="have_a_child" id="have_a_child" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="have_a_child" id="have_a_child1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> Do you have pets that affect your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="have_a_pets" id="have_a_pets" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="have_a_pets" id="have_a_pets1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> Does heartburn or indigestion affect your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="heartburn_or_indigestion" id="heartburn_or_indigestion" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="heartburn_or_indigestion" id="heartburn_or_indigestion1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> Does your work affect your sleep patterns and times? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="work_affect" id="work_affect" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="work_affect" id="work_affect1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> Does your work stress affect your sleep? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="work_stress" id="work_stress" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="work_stress" id="work_stress1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="step">
                                <h2 class="section_title"> Do you wake up in the night and go to the rest room? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="wakeup_in_night" id="wakeup_in_night" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="wakeup_in_night" id="wakeup_in_night1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="submit step" id="end">
                                <h2 class="section_title"> Do you wake up in the night with an active mind? </h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="wakeup_with_active" id="wakeup_with_active" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                        <input type="radio" name="wakeup_with_active" id="wakeup_with_active1" value="No">
                                        <span class="checkmark"></span>
                                    </label>
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
        $( "#datepicker" ).datepicker({ 
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',
            dateFormat : 'yy-mm-dd',
        });

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


