@extends('Result.masters.app')

@section('page-title')
<span >Benchmarks</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('result/css/custom.css?v='.time()) !!}


<!-- VpForm -->
{!! Html::style('result/vendor/vp-form/css/vp-form.css?v='.time()) !!}




@stop
@section('content')

<style>
    #ui-datepicker-div{
        z-index: 1000!important;
    }
</style>
<!-- start: acc1 -->
<div class="panel panel-white vp-form" ng-app="vpFormBM">

    <div class="starting-screen fade-in hidden" ng-controller="BMController">
        <div class="enter-btn active">
            <button type="button" class="btn btn-primary" ng-click="startFormInput()">
                Start <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            <span class="press-enter">press <b>ENTER</b> to continue</span>

            <div class="starting-screen-input-container">
                <div class="starting-screen-input-overlay"></div>
                <input id="input-starting-screen" type="text" ng-keypress="pressEnter($event)">
            </div>
        </div>
    </div>

    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span> 
            Progression
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">

        <div class="page-header">
            <h1>Progression
                <button class="btn btn-primary m-t-10 pull-right" id="showBenchmarkForm">Create benchmark</button>
                <button class="btn btn-default m-t-10 pull-right hidden " id="hideBenchmarkForm"> Cancel</button>

            </h1>
        </div>

        <input type ="hidden" value = "{{ $clients->id }}" class = "client-id">    
        <div id="createBenchmark"  class="hidden">
            @include('Result.benchmark.view.benchmark_add')
        </div>

        <div id="benchmark-list" class="container-fluid container-fullw" style="border-bottom:none !important; padding-bottom:0 !important">
            <div class="row accordion-div">
                @include('Result.benchmark.view.benchmark_list',['benchmarks_details'=>$benchmarks])
            </div>
        </div>



        <!-- start: benchmarke Details Field -->
        <div id="benchmarke-details-field" class="hidden">
            <div id="benchmark-data-area">
                @include('Result.partials.benchmark_overview')
            </div>
        </div> 
        <!-- end: benchmarke Details Field -->

    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc1 -->


@endsection

@section('required-script')
    {!! Html::script('result/vendor/vp-form/js/angular.js?v='.time()) !!}
    {!! Html::script('result/vendor/vp-form/js/autogrow.js') !!}
    {!! Html::script('result/vendor/vp-form/js/vp-form-benchmark.js?v='.time()) !!}
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




{!! Html::script('result/js/form-wizard-clients.js?v='.time()) !!}
{!! Html::script('result/js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('result/js/benchmark.js?v='.time()) !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('result/js/bench.js?v='.time()) !!}
{!! Html::script('result/js/clients.js?v='.time()) !!}




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
<!--FormWizard.init();-->

@stop()