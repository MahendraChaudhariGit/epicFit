@extends('blank')

@section('plugin-css')
    {!! Html::style('assets/css/plugins.css?v='.time()) !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}

    <!-- start: Bootstrap datepicker --> 
    {!! Html::style('vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css') !!} 
    <!-- end: Bootstrap datepicker --> 

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->

    {!! HTML::style('assets/plugins/fitness-planner/custom/style.css') !!} 
    {!! Html::style('assets/plugins/fitness-planner/css/api.css') !!}
@stop

@section('page-title')
    Generate Program
@stop

@section('content')
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->

    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!-- Start: all generator modal here -->
    @include('includes.partials.activities_generator_helper')
    <!-- End: all generator modal here -->

    <!-- start: plan design -->
    @include('includes.partials.add_exercise_modal',['exerciseData'=>$exerciseData])
    <!-- start: plan design -->
    <div id="alertDiv" class="hidden"></div>

    <!-- start: plan design -->
    <div id="libraryActivityPlanner">
        <button class="btn hidden" id="openGeneratePlan" data-target-step="trainingSegment"></button>
        @include('includes.partials.activities_planner_helper')
    </div>
    <!-- End: Plan design -->
    
@stop

@section('script')
    <!-- Start:  NEW timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
    <!-- End: NEW timepicker js --> 

    <!-- Start: Movement -->
    {!! Html::script('assets/js/movement.js?v='.time()) !!}
    <!-- End: Movement -->

    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
    {!! Html::script('assets/js/helper.js?v='.time()) !!}

   <!-- Start: Activity Planner -->
    {!! Html::script('assets/js/fitness-planner/api.js?v='.time()) !!} 
    {!! Html::script('assets/js/fitness-planner/bodymapper.js?v='.time()) !!}
    {!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js') !!}
    {!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}
    <!-- ENd: Activity Planner -->
    <script>
        jQuery(document).ready(function() {
            //Metronic.init();
            $( ".panel-collapse.closed" ).trigger( "click" );
        });
    </script>

    {!! Html::script('assets/js/fitness-planner/generator-helper.js?v='.time()) !!}
@stop