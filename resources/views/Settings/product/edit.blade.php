@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent
    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css?v='.time()) !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
    <!-- end: JCrop -->
@stop

@section('page-title')
    @if(isset($product))
        Edit
    @else
        Add
    @endif
    Product
@stop

@section('form')
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!-- start: Add More Model -->
    @include('includes.partials.add_more_modal')
    <!-- end: Add More Model -->

    <!-- start: Add More Model -->
    @include('includes.partials.add-edit-subcategory')
    <!-- end: Add More Model -->
    
    @include('Settings.product.form')
@stop

@section('script')
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
    {!! Html::script('assets/plugins/ckeditor/ckeditor.js?v='.time()) !!}
    {!! Html::script('assets/plugins/ckeditor/adapters/jquery.js?v='.time()) !!}
    {!! Html::script('assets/js/ckeditor.js?v='.time()) !!}
    {!! Html::script('assets/js/product.js?v='.time()) !!}
    <!-- end: JCrop -->
@stop()