@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
    <!-- end: JCrop -->
@stop

@section('page-title')
    Edit Business
@stop

@section('form')
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!-- start: Add More Model -->
        @include('includes.partials.add_more_modal')
    <!-- end: Add More Model -->
    
    @include('Settings.business.form')
@stop

@section('script')
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
    <!-- end: JCrop -->
@stop()