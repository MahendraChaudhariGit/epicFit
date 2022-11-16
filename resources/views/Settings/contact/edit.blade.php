@extends('Settings.business_setup')

@if(!isset($subview))
	@section('page-title')
	    @if(isset($contact))
	    	Edit
	    @else
	    	Add
	    @endif
	    Contact
	@stop
@endif

@section('form')

	<!-- start: Add More Model -->
    @include('includes.partials.add_more_modal')
    <!-- end: Add More Model -->
    
    @include('Settings.contact.form')
@stop