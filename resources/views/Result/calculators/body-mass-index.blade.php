@extends('Result.masters.app')
@section('page-title')
<span >Body Mass Index Calculator</span> 
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
<!--New section start from here-->

<div class="panel panel-white panel-white-new panel-white panel-white-new-new">
<div class="panel-body" style="">
    <div class="alert alert-success" style="display:none">
      
    </div>
    <div class="row">
		<div class="col-md-6">
			<div class="calculator-banner-img">
				<img src="{{ asset('result/images/calcul-2.jpeg') }}" alt="" class="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="left-div-calculator">
			<div class="left-cal">
				<h4>Cu affert populo neglegentur has, labore nostrum periculis ius in, singulis electram ad vel. Ubique ceteros mediocritatem eos .</h4>              
                <form id="form">
                            <div class="form-group"><legend class="strong">Input Type </legend>
                                <input type="hidden" name="record_id" id="record_id" value="{{ $clientData['id'] }}"> 
                                <div class="body-mass-index">
                                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                        <input type="radio" name="type" id="gridRadios1" value="metric"  {{ ($clientData['type'] == "metric")? "checked" : "" }}>
                                        <label for="gridRadios1"> Metric System </label>
                                    </div>
                                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                        <input type="radio" name="type" id="gridRadios2" value="imperial" {{ ($clientData['type'] == "imperial")? "checked" : "" }}>
                                        <label for="gridRadios2"> Imperial System </label>
                                    </div>
                                </div>
                            </div>
                            <div id="metric">
                                <div class="form-group">
                                    <div class="">
                                        <input type="number" class="form-control" placeholder="Weight (kg)" value="{{ $clientData['weight'] }}" name="weight-m" id="weight_m" required>
                                        <span id="wei" class="help-block" style="color: #a94442;display: none;" ></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <input type="number" class="form-control" placeholder="Height (cm)" value="{{ $clientData['height_ft'] }}" name="height-m" id="height_m" required>
                                        <span id="hei" class="help-block" style="color: #a94442;display: none;" ></span>
                                    </div>
                                </div>
                            </div>
                            <div id="imperial" style="display: none;">
                                <div class="form-group">
                                    <div class="">
                                        <input type="number" class="form-control" id="weight" placeholder="Weight (lbs)" value="{{ $clientData['weight'] }}" name="weight-i" required>
                                        <span id="wei_i" class="help-block" style="color: #a94442;display: none;" ></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <input type="number" class="form-control" id="height_ft" placeholder="Height (ft)" value="{{ $clientData['height_ft'] }}" name="height-i-ft" required>
                                        <span id="hei_ft" class="help-block" style="color: #a94442;display: none;" ></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <input type="number" class="form-control" id="height_in" placeholder="Height (in)" value="{{ $clientData['height_in'] }}" name="height-i-in" required>
                                        <span id="hei_in" class="help-block" style="color: #a94442;display: none;" ></span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary button" id="Calculate-new">Calculate</button>
                            <div class="form-group"></div>
                            <button type="button" class="btn btn-primary button_edit" id="Calculate-new">Edit</button>
                            </div>
                            <div class="right-cal right-cal-new">
                                <!--div class="result-cal-new"><h3>Result</h3></div-->
                            <div class="form-group" id="result" style="display:block !important;">
                                <div class="">
                                    <div class="">
                                        <div class="counter-new">
                                        <div class="form-check">
                                            <!--div class="col-md-3"><label class="form-check-label">BMI :</label></div-->
                                            <div class=""><span>BMI</span><input class="form-control-plaintext bmi" value="{{ $clientData['bmi'] }}" type="text" readonly></div>
                                        </div>
                                        </div>
                                        <div class="counter-new">
                                        <div class="form-check">
                                            <!--div class="col-md-3"><label class="form-check-label">Classification :</label></div-->
                                        <div class=""><span>Classification</span><input class="form-control-plaintext classification" value="{{ $clientData['clasification'] }}" type="text" readonly></div>
                                        </div>
                                        </div>
                                        <div class="counter-new">
                                        <div class="form-check">
                                        <!--div class="col-md-3">
                                            <label class="form-check-label">Weight Range :</label>
                                        </div-->
                                        <div class="">
                                        <span>Weight Range :</span>
                                            <input class="form-control-plaintext weight-range" value="{{ $clientData['weight_renge'] }}" type="text" readonly>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                </form>
			</div>
		</div>
    </div>
</div> 
</div>
<!--New section ends here-->

<div class="panel panel-white panel-white-new panel-white panel-white-new-new" style="display:none;">
<div class="panel-body">
    <div class="row">
    <div class="col-md-8 col-sm-12">
        <form id="form">
            <fieldset class="form-group">
			                    <legend class="strong">Input Type </legend>
                <div class="row">
                    <div class="form-group">
                    <div>
                      <div class="radio clip-radio radio-primary radio-inline m-b-0">
                        <input type="radio" name="type" id="gridRadios1" value="metric" checked>
                            <label for="gridRadios1"> Metric System </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="type" id="gridRadios2" value="imperial">
                            <label for="gridRadios2"> Imperial System </label>
                        </div>
                    </div>
					</div>
                    
                </div>
                
            </fieldset>
            <div id="metric">

                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Weight (kg)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Weight (kg)" name="weight-m" id="weight_m" required>
                        <span id="wei" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height" class="col-sm-2 col-form-label">Height (cm)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Height (cm)" name="height-m" id="height_m" required>
                        <span id="hei" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
            </div>
            <div id="imperial" style="display: none;">
                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Weight (lbs)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="weight" placeholder="Weight (lbs)" name="weight-i" required>
                        <span id="wei_i" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height-ft" class="col-sm-2 col-form-label">Height (ft)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="height_ft" placeholder="Height (ft)" name="height-i-ft" required>
                         <span id="hei_ft" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height-in" class="col-sm-2 col-form-label">Height (in)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="height_in" placeholder="Height (in)" name="height-i-in" required>
                        <span id="hei_in" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
            </div>
            <fieldset class="form-group" id="result" style="display: none">
                <div class="row">
                    <legend class="col-form-legend">Result</legend>
                    <div class="col-sm-12">
                        <div class="row">
                        <div class="form-check">
                            <div class="col-md-3"><label class="form-check-label">BMI :</label></div>
							<div class="col-md-9"><input class="form-control-plaintext bmi" type="text" readonly></div>
                        </div>
                        </div>
						<div class="row">
                        <div class="form-check">
                            <div class="col-md-3"><label class="form-check-label">Classification :</label></div>
					    <div class="col-md-9"><input class="form-control-plaintext classification" type="text" readonly></div>
                        </div>
                        </div>
						<div class="row">
                        <div class="form-check">
						<div class="col-md-3">
                            <label class="form-check-label">Weight Range :</label>
						</div>
						<div class="col-md-9">
							<input class="form-control-plaintext weight-range" type="text" readonly>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <button type="button" class="btn btn-primary button">Calculate</button>
        </form>
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
