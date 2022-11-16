@extends('Result.masters.app')

@section('page-title')
    <span >EPIC PROCESS- SUMMARY</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('result/css/custom.css?v='.time()) !!}
<style>
    .swMain.wizard-headding-style > ul{position:static;margin-bottom:25px}
    .wizard-headding-style .control-label{text-align:left}
</style>

@stop
@section('content')
<!--<div class="page-header">
    <h1>Epic process</h1>
</div>-->

<!-- start: acc1 -->
<div class="panel panel-white">
            <input id="selected-step" type="hidden" value="{{$id}}">

    <!-- start: PANEL HEADING -->
    <div class="panel-heading" style="display:none;">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span> 
            Assess & Progress
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                	<i class="fa fa-wrench"></i>
                </a>
            	<a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-process" data-wizard-id="wizard">
                	<i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
        <form action="#" role="form" class="smart-wizard" id="wizard-form" data-form-mode="view"><!--form-horizontal-->
            {!! Form::token() !!}
            <input id="client_id" type="hidden" name="client_id" value="{{$parq->client_id}}">
            <input type="hidden" name="step_status" value="{{$parq->parq1}}, {{$parq->parq2}}, {{$parq->parq3}}, {{$parq->parq4}}, {{$parq->parq5}}" data-parq1="{{$parq->parq1}}" data-parq2="{{$parq->parq2}}" data-parq3="{{$parq->parq3}}" data-parq4="{{$parq->parq4}}" data-parq5="{{$parq->parq5}}">
            <map name="Map" id="Map"></map>
            <div id="wizard" class="swMain parqForm">
                  <ul>
                    <li>
                        <a href="#step-1" class="openStep" data-url="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}">
                            <div class="stepNumber">
                                1
                            </div>
                            <span class="stepDesc"><small>Personal Details</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-2" id="step2" class="openStep done" data-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}">
                            <div class="stepNumber">
                                2 </div>
                            <span class="stepDesc"><small>Exercise Preference</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3" class="openStep" data-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}">
                            <div class="stepNumber">
                                3
                            </div>
                            <span class="stepDesc"><small>Injury Profile &amp; Family History</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-4" class="openStep" data-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}">
                            <div class="stepNumber">
                                4
                            </div>
                            <span class="stepDesc"><small>PARQ</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-5" class="openStep" data-url="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}">
                            <div class="stepNumber">
                                5
                            </div>
                            <span class="stepDesc"><small>Goals & Motivation</small></span>
                        </a>
                    </li>
                </ul>
                <!-- start: WIZARD STEP 1 -->
                <div id="step-1">
                    @include('Result.parq.view.parq-step1')
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <a href="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" class="btn btn-primary btn-o  btn-wide pull-right">
                             Next <i class="fa fa-arrow-circle-right"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 1 -->
                
                <!-- start: WIZARD STEP 2 -->
                <div id="step-2">
                    <div class="sucMes hidden">
                        {!! displayAlert()!!}
                    </div>
                    <div class="row">
                        @include('Result.parq.view.parq-step2')
                    </div>
                    <div class="row">
                       <div class="col-sm-6">
                            <a href="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" class="btn btn-primary btn-o btn-wide pull-left">
                                <i class="fa fa-arrow-circle-left"></i> Back
                            </a>
                             
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" class="btn btn-primary btn-o  btn-wide pull-right">
                                Next <i class="fa fa-arrow-circle-right"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 2 -->
                    
                <!-- start: WIZARD STEP 3 -->
                <div id="step-3">
                    @include('Result.parq.view.parq-step3')
                    <div class="row">
                        <div class="col-sm-6">
                             <a href="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" class="btn btn-primary btn-o  btn-wide pull-left">
                                <i class="fa fa-arrow-circle-left"></i> Back
                            </a>
                             
                        </div>
                        <div class="col-sm-6">
                            
                             <a href="{{ url('epicprogress/AssessAndProgress/PARQ') }}" class="btn btn-primary btn-o  btn-wide pull-right">
                                Next <i class="fa fa-arrow-circle-right"></i>
                            </a>


                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 3 -->
                        
                <!-- start: WIZARD STEP 4 -->
                <div id="step-4">
                    <div class="sucMes hidden">
                        {!! displayAlert()!!}
                    </div>
                    <div class="row">
                        @include('Result.parq.view.parq-step4')
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                             <a href="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" class="btn btn-primary btn-o btn-wide pull-left">
                                <i class="fa fa-arrow-circle-left"></i> Back
                            </a>
                             
                        </div>
                        <div class="col-sm-6">
                           <a href="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}" class="btn btn-primary btn-o  btn-wide pull-right">
                                Next <i class="fa fa-arrow-circle-right"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 4 -->
                        
                <!-- start: WIZARD STEP 5 -->
                <div id="step-5">
                    <div class="row">
                        @include('Result.parq.view.parq-step5')
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ url('epicprogress/AssessAndProgress/PARQ') }}" class="btn btn-primary btn-o  btn-wide pull-left">
                                <i class="fa fa-arrow-circle-left"></i> Back
                            </a>
                             
                        </div>
                        <!--<div class="col-sm-6">
                            <button class="btn btn-primary btn-o btn-wide pull-right" id="finish-parq-summary">
                                Finish <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div> --> 
                        <a href="{{route('dashboard')}}" class="btn btn-primary btn-o  btn-wide pull-right" style="margin-left: 20px;">
                                Exit <i class="fa fa-arrow-circle-right"></i>
                            </a>
                    </div>
                </div>
                <!-- end: WIZARD STEP 5 -->
                        
                <!--<div class="clear-widget"></div>-->
            </div>
        </form>
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc1 -->

<!--Start: Extra field -->

<!--End: Extra field -->

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




 {!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/js/form-wizard-clients.js?v='.time()) !!}


<script>
    var loggedInUser = {
            type: '{{ Auth::user()->account_type }}',
            id: '{{ Auth::user()->account_id }}',
            name: '{{ Auth::user()->name }}'
        },
     popoverContainer = $('#container');    
</script>





@stop
@section('script-handler-for-this-page')
 $( ".panel-collapse.closed" ).trigger( "click" );
    FormWizard.init('#wizard');

@stop()