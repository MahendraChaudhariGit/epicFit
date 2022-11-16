@extends('Result.masters.app')
@section('page-title')
<span >Lean Body Mass Calculator</span> 
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
        <form id="form" class="lean-body-mass">
            <input type="hidden" name="record_id" id="record_id" value="{{ $clientData['id'] }}">
            <div class="form-group"><legend class="strong">Input Type </legend>
                <div class="">
                    <div class="form-group">
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
                        <input type="number" class="form-control" id="height_ft" placeholder="Height (ft)" name="height-i-ft" value="{{ $clientData['height'] }}"required>
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
            <button type="button"  id="Calculate-new"  class="btn btn-primary button_lean">Calculate</button>
            <div class="form-group"></div>
            <button type="button" class="btn btn-primary button_lean_edit" id="Calculate-new">Edit</button>
            </div>
			<div class="right-cal right-cal-new">
            <div class="form-group" id="result" style="">
                <div class="">
                    <div class="">
                        <div class="counter-new">
                        <div class="form-check">
                            <div class="form-check-label">
                                <span>Lean Mass :</span>
								 <input value="{{ $clientData['lm'] }} unit ({{ $clientData['lmp'] }} %)" class="form-control-plaintext lm" type="text" readonly>
                            </div>
                        </div>
                        </div>
                        <div class="counter-new">
                        <div class="form-check">
                            <div class="form-check-label">
                                <span>Fat Mass :</span>
								<input value="{{ $clientData['fm'] }} unit ({{ $clientData['fmp'] }} %)" class="form-control-plaintext fm" type="text" readonly>
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

