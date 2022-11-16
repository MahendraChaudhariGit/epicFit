@extends('Result.masters.app')
@section('page-title')
<span >Calorie Breakdown Calculator</span> 
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
        <form id="form" class="calorie-breakdown">
            <input type="hidden" name="record_id" id="record_id" value="{{ $clientData['id'] }}"> 
            <div class="form-group"><legend class="strong">Gender</legend>
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
                <div class="form-group">
                    <div class="">
                        <input type="number" class="form-control" placeholder="Age" name="age" id="age" value="{{ $clientData['age'] }}" required>
                        <span id="ag" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="">
                        <input type="number" class="form-control" placeholder="Daily Calorie Intake" name="calorie" id="calorie"  value="{{ $clientData['calorie'] }}" required>
                        <span id="cal" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
            </div>
            <button type="button"  id="Calculate-new"  class="btn btn-primary button_calorie">Calculate</button>
			<div class="form-group"></div>
            <button type="button" class="btn btn-primary button_calorie_edit" id="Calculate-new">Edit</button>
            </div>
			<div class="right-cal right-cal-new">
            <div id="result" style="">
                <ul class="list-group list-group-new-list">
                    <li class="list-group-item fat">Fat : <span>15% - 25% {{ $clientData['fatl'] }} - {{ $clientData['fath'] }} calories </span></li>
                    <li class="list-group-item protein">protein : <span>15% - 25% {{ $clientData['proteinl'] }} - {{ $clientData['proteinh'] }} calories </span></li>
                    <li class="list-group-item carb">Total Carbohydrates : <span>50% - 70% {{ $clientData['carbohydratel'] }} - {{ $clientData['carbohydrateh'] }} calories</span></li>
                    <li class="list-group-item fiber">Fiber : <span>{{ $clientData['fiber'] }} grams</span></li>
                    <li class="list-group-item sugar">Sugars : <span><25% < {{ $clientData['sugar'] }} calories </span></li>
                </ul>
            </div>
            <br><br>
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
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.j') !!}
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
<!--{!! Html::script('js/events.js?v='.time()) !!}-->
{!! Html::script('result/js/calculator.js?v='.time()) !!}
{!! Html::script('result/js/clients.js?v='.time()) !!}

@stop
