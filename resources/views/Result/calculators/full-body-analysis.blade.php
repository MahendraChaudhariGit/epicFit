@extends('Result.masters.app')
@section('page-title')
<span >Full Body Analysis Calculator</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('result/css/custom.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')
    
<div class="panel panel-white panel-white-new">
<div class="panel-body">  
<div class="alert alert-success" style="display:none"> </div>  
    <div class="row">
		<div class="col-md-6">
			<div class="calculator-banner-img">
				<img src="{{ asset('result/images/calcul-2.jpeg') }}" alt="" class="">
			</div>
		</div>
        <div class="col-md-6 col-sm-12">
				<div class="left-div-calculator">
				<div class="left-cal">
				<h4>Cu affert populo neglegentur has, labore nostrum periculis ius in, singulis electram ad vel. Ubique ceteros mediocritatem eos .</h4>
        <form id="form" class="full-body-analysis">
            <input type="hidden" name="record_id" id="record_id" value="{{ $clientData['id'] }}">
            <div class="form-group">
                <div class="">
                    <div class="form-group"><legend class="strong">Input Type </legend>
                        <div>
                          <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="type" id="gridRadios1" value="metric" {{ ($clientData['type'] == "metric")? "checked" : "" }}>
                            <label for="gridRadios1"> Metric System </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="type" id="gridRadios2" value="imperial" {{ ($clientData['type'] == "imperial")? "checked" : "" }}>
                            <label for="gridRadios2"> Imperial System </label>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group"><legend class="strong">Gender </legend>
                <div class="">
                    <div class="form-group">
                        <div>
                          <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="gridRadios3" value="male" {{ ($clientData['gender'] == "male")? "checked" : "" }}>
                            <label for="gridRadios3"> Male </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="gridRadios4" value="female" {{ ($clientData['gender'] == "female")? "checked" : "" }}>
                            <label for="gridRadios4"> Female </label>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="metric">
                <div class="form-group ">
                    <div class="">
                        <input type="number" class="form-control" placeholder="Weight (kg)" name="weight-m" id="weight_m" value="{{ $clientData['weight'] }}" required>
                        <span id="wei" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        <input type="number" class="form-control" placeholder="Height (cm)" name="height-m" id="height_m" value="{{ $clientData['height_ft'] }}" required>
                        <span id="hei" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
            </div>
            <div id="imperial" style="display: none;">
                <div class="form-group">
                    <div class="">
                        <input type="number" class="form-control" id="weight" placeholder="Weight (lbs)" name="weight-i" value="{{ $clientData['weight'] }}" required>
                        <span id="wei_i" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        <input type="number" class="form-control" id="height_ft" placeholder="Height (ft)" name="height-i-ft" value="{{ $clientData['height_ft'] }}" required>
                        <span id="hei_ft" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        <input type="number" class="form-control" id="height_in" placeholder="Height (in)" name="height-i-in" value="{{ $clientData['height_in'] }}" required>
                        <span id="hei_in" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="">
                    <input type="number" class="form-control" placeholder="Age" name="age" id="age" value="{{ $clientData['age'] }}" required>
                    <span id="ag" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
            </div>
            <div class="form-group">
                <div class="">
                    <input type="number" class="form-control" placeholder="Resting Heart Rate Average" name="rhra" id="heart_rate" value="{{ $clientData['rhra'] }}" required>
                    <span id="hr" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
            </div>
            <div class="form-group">
                <div class="">
                    <input type="number" class="form-control" placeholder="Waist (at narrowest)" name="waist" id="waist_m" value="{{ $clientData['waist'] }}" required>
                    <span id="waist" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
            </div>
            <div class="form-group ">
 
                <div class="">
                    <input type="number" class="form-control" placeholder="Hip (at widest)" name="hip" id="hip_m" value="{{ $clientData['hip'] }}" required>
                    <span id="hip_i" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
            </div>
            <div class="form-group ">
                <div class="">
                    <input type="number" class="form-control" placeholder="Elbow Width" name="elbow" id="elbow_m" value="{{ $clientData['elbow'] }}" required>
                    <span id="elbow" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
            </div>
            <div class="form-group">
                <div class="">
                    <div class="form-group">
					<legend class="strong">Activity Level </legend>
                        <div>
                          <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="activity" id="gridRadios5" value="sedentary" {{ ($clientData['activity'] == "sedentary")? "checked" : "" }}>
                            <label for="gridRadios5"> Sedentary </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="activity" id="gridRadios6" value="lightly-active" {{ ($clientData['activity'] == "lightly-active")? "checked" : "" }}>
                            <label for="gridRadios6"> Lightly Active </label>
                        </div>
                        <div class="radio clip-radio clip-radio-new radio-primary radio-inline m-b-0">
                            <input type="radio" name="activity" id="gridRadios7" value="moderately-active" {{ ($clientData['activity'] == "moderately-active")? "checked" : "" }}>
                            <label for="gridRadios7"> Moderately Active </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="activity" id="gridRadios8" value="very-active" {{ ($clientData['activity'] == "very-active")? "checked" : "" }}>
                            <label for="gridRadios8"> Very Active </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="activity" id="gridRadios9" value="extremely-active" {{ ($clientData['activity'] == "maleextremely-active")? "checked" : "" }}>
                            <label for="gridRadios9"> Extremely Active </label>
                        </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="form-group">
                        <legend class="strong">Fitness Goal </legend>
                <div class="">
                    <div class="form-group">
                        <div>
                          <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="goal" id="gridRadios10" value="get-fit" {{ ($clientData['goal'] == "get-fit")? "checked" : "" }}>
                            <label for="gridRadios10"> Get Fit </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="goal" id="gridRadios11" value="lose-weight" {{ ($clientData['goal'] == "lose-weight")? "checked" : "" }}>
                            <label for="gridRadios11"> Lose Weight </label>
                        </div>
                        <div class="radio clip-radio clip-radio-new radio-primary radio-inline m-b-0">
                            <input type="radio" name="goal" id="gridRadios12" value="increase-endurance" {{ ($clientData['goal'] == "increase-endurance")? "checked" : "" }}>
                            <label for="gridRadios12"> Increase Endurance </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="goal" id="gridRadios13" value="excellent-fitness" {{ ($clientData['goal'] == "excellent-fitness")? "checked" : "" }}>
                            <label for="gridRadios13"> Excellent Fitness </label>
                        </div>
                        <div class="radio clip-radio clip-radio-new radio-primary radio-inline m-b-0">
                            <input type="radio" name="goal" id="gridRadios14" value="competitive-athletics" {{ ($clientData['goal'] == "competitive-athletics")? "checked" : "" }}>
                            <label for="gridRadios14"> Competitive Athletics </label>
                        </div>
                        </div>
                    </div>
                </div>
                    <button type="button"  id="Calculate-new"  class="btn btn-primary button_full_body">Calculate</button>
                    <div class="form-group"></div>
            <button type="button" class="btn btn-primary button_full_body_edit" id="Calculate-new">Edit</button>
                </div>
				</div>
				<div class="right-cal right-cal-new">

            <div id="result" style="">
                <ul class="list-group list-group-new-list">
                    <li class="list-group-item bmi">Body Mass Index (BMI) : <span>{{ $clientData['bmi'] }}</span></li>
                    <li class="list-group-item bmic">BMI Classification : <span>{{ $clientData['classification'] }} ( {{ $clientData['weight_range'] }} )</span></li>
                    <li class="list-group-item whr">Waist to Hip Ratio : <span>{{ $clientData['ratio'] }} %</span></li>
                    <li class="list-group-item bs">Body Shape : <span>{{ $clientData['bs'] }}</span></li>
                    <li class="list-group-item interpretation">Interpretation : <span>{{ $clientData['interpretation'] }}</span></li>
                    <li class="list-group-item iw">Ideal Weight : <span>{{ $clientData['ideal_weight'] }} kg</span></li>
                    <li class="list-group-item bf">Body Fat : <span>{{ $clientData['fm'] }} kg {{ $clientData['fmp'] }} %</span></li>
                    <li class="list-group-item lm">Lean Mass : <span>{{ $clientData['lm'] }} kg {{ $clientData['lmp'] }} %</span></li>
                    <li class="list-group-item rm">Resting Metabolism (RMR) : <span>{{ $clientData['arm'] }}  cal/day {{ $clientData['arm']/24 }} cal/hour</span></li>
                    <li class="list-group-item aam">Average Actual Metabolism : <span>{{ $clientData['aam'] }}  cal/day {{ $clientData['aamph'] }} cal/hour</span></li>
                    <li class="list-group-item kthr">Karvonen Target Heart Rate (THR) : <span>{{ $clientData['bpml'] }}  - {{ $clientData['bpmh'] }} bpm {{ $clientData['bptsl'] }} - {{ $clientData['bptsh'] }} b/10s</span></li>
                    <li class="list-group-item mhr">Maximum Heart Rate (MHR) : <span>{{ $clientData['mhr'] }} - {{ $clientData['mhrits'] }} b/10s</span></li>
                </ul>
            </div>
            
        </form>
		</div>
    </div>
    </div>
    </div>
</div>
</div>
    
@endsection

@section('required-script')

{!! Html::script('result/js/jquery-ui.min.js') !!}

<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js') !!}
<!-- end: Moment Library -->

<!-- start: Summernote -->
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
<!-- end: Summernote -->
<!-- start: Rating -->
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
<!-- end: Rating -->
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap timepicker -->

{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
<!-- end: Bootstrap timepicker -->
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}


{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
<!-- start: image upload js -->
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
<!-- start: image upload js -->

{{-- {!! Html::script('result/js/form-wizard-clients.js?v='.time()) !!} --}}
{!! Html::script('result/js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('result/js/benchmark.js?v='.time()) !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('result/js/calculator.js?v='.time()) !!}
{!! Html::script('result/js/clients.js?v='.time()) !!}

@stop
