@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
    <!-- end: JCrop -->

    <!-- start: Sweet Alert -->
    {!! Html::style('vendor/sweetalert/sweet-alert.css?v='.time()) !!}
    <!-- end: Sweet Alert -->
@stop

@if(!isset($subview))
    @section('page-title')
        @if(isset($service))
            Edit Service
        @elseif(isset($class))   
            Edit Class
        @elseif(isset($entityType))
            @if($entityType == 'service')
                Add Service
            @elseif($entityType == 'class')
                Add Class
            @endif
        @endif
    @stop
@endif    

@section('form')
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!-- start: Add More Model -->
    @include('includes.partials.add_more_modal')
    <!-- end: Add More Model -->
    
    @include('Settings.service.form')
@stop

@section('script')
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
    <!-- end: JCrop -->

    <!-- start: Sweet Alert -->
    {!! Html::script('vendor/sweetalert/sweet-alert.min.js?v='.time()) !!}
    <!-- end: Sweet Alert -->
@stop()