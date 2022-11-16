@extends('Result.masters.app')

@section('required-styles') 
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
    {!! Html::style('result/css/custom.css?v='.time()) !!}
     
@stop

@section('page-title')
<span >Nutrition Calculator</span> 
@stop

@section('content')
	@include('includes.partials.waiting_shield')
	<div id="panel_edit_account" class="tab-pane active">
	    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
	    <div class="row swMain">
	        @include('mealplanner.tools.form')
	    </div>
	</div>
	@include('includes.partials.pic_crop_model')

@endsection

@section('script')
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('assets/js/validator-helper.js?v='.time()) !!}
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
{!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}

{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/meal-tools.js?v='.time()) !!}
@stop