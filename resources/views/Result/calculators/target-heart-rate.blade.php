@extends('Result.masters.app')
@section('page-title')
<span >Target Heart Rate Calculator</span> 
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
        <form id="form" class="target-heart-rate">
            <div class="form-group"><legend class="strong">Fitness Goal</legend>
                <input type="hidden" name="record_id" id="record_id" value="{{ $clientData['id'] }}"> 
                <div class="">
                    <div class="form-group">
                    <div>
                      <div class="radio clip-radio radio-primary radio-inline m-b-1">
                            <input type="radio" name="goal" id="gridRadios1" value="get-fit" {{ ($clientData['goal'] == "get-fit")? "checked" : "" }}>
                            <label for="gridRadios1">  Get Fit </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-1">
                            <input type="radio" name="goal" id="gridRadios2" value="lose-weight" {{ ($clientData['goal'] == "lose-weight")? "checked" : "" }}>
                            <label for="gridRadios2">  Lose Weight </label>
                        </div>
                        <div class="radio clip-radio clip-radio-new radio-primary radio-inline m-b-2">
                            <input type="radio" name="goal" id="gridRadios3" value="increase-endurance" {{ ($clientData['goal'] == "increase-endurance")? "checked" : "" }}>
                            <label for="gridRadios3">  Increase Endurance</label>
                        </div>
                        <div class="radio clip-radio clip-radio-new radio-primary radio-inline m-b-3">
                            <input type="radio" name="goal" id="gridRadios4" value="excellent-fitness" {{ ($clientData['goal'] == "excellent-fitness")? "checked" : "" }}>
                            <label for="gridRadios4">  Excellent Fitness</label>
                        </div>
                        <div class="radio clip-radio clip-radio-new radio-primary radio-inline m-b-4">
                            <input type="radio" name="goal" id="gridRadios5" value="competitive-athletics" {{ ($clientData['goal'] == "competitive-athletics")? "checked" : "" }}>
                            <label for="gridRadios5">  Competitive Athletics</label>
                        </div>
                    </div>
                  </div>
                    
                </div>
            </div>
            <div id="metric">
                <div class="form-group">
                    <div class="">
                        <input type="number" class="form-control" placeholder="Age" name="age" value="{{ $clientData['age'] }}" id="age" required>
                        <span id="ag" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="">
                        <input type="number" class="form-control" placeholder="Resting Heart Rate Average" name="rhra" value="{{ $clientData['rhra'] }}" id="heart_rates" required>
                        <span id="heart_rate" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary button_target" id="Calculate-new">Calculate</button>
             <div class="form-group"></div>
            <button type="button" class="btn btn-primary button_target_edit" id="Calculate-new">Edit</button>
			</div>
			<div class="right-cal right-cal-new">
            <div class="form-group" id="result1" style="">
                <div class="">
                <div class="">
                    <div class="counter-new">
                        <div class="form-check">
                            <label class="form-check-label">
                                <span>Beats Per Minute :</span>
                                <input class="form-control-plaintext bpm" value="{{ $clientData['bpml'] }} - {{ $clientData['bpmh'] }} bpm" type="text" readonly>
                            </label>
                        </div>
                    </div>
                    <div class="counter-new">
                        <div class="form-check">
                            <label class="form-check-label">
                                <span>Beats In 10 Secs :</span>
                                <input class="form-control-plaintext bpts" value="{{ $clientData['bptsl'] }} - {{ $clientData['bptsh'] }}" type="text" readonly>
                            </label>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="form-group" id="result2" style="">
                <div class="">
                <div class="">
                    <div class="counter-new">
                        <div class="form-check">
                            <label class="form-check-label">
                                <span>Beats Per Minute :</span>
                                <input class="form-control-plaintext mhr" value="{{ $clientData['mhr'] }}" type="text" readonly>
                            </label>
                        </div>
                    </div>
                    <div class="counter-new">
                        <div class="form-check">
                            <label class="form-check-label">
                                <span>Beats In 10 Secs :</span>
                                <input class="form-control-plaintext mhrits" value="{{ $clientData['mhrits'] }}" type="text" readonly>
                            </label>
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
