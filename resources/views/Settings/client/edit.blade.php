@extends('Settings.business_setup')

@if(isset($client))
	@section('required-styles-for-this-page')
	    @parent

	    <!-- start: Sweet Alert -->
	    {!! Html::style('vendor/sweetalert/sweet-alert.css?v='.time()) !!}
	    <!-- end: Sweet Alert -->
	@stop
@endif

@if(!isset($subview))
	@section('page-title')
	    @if(isset($client))
	    	Edit
	    @else
	    	Add
	    @endif
	    Client
	@stop
@endif

@section('form')
    @include('Settings.client.form')
@stop

@if(isset($client))
	@section('script')
	    <!-- start: Sweet Alert -->
	    {!! Html::script('vendor/sweetalert/sweet-alert.min.js?v='.time()) !!}
	    <!-- end: Sweet Alert -->
	@stop()
@endif