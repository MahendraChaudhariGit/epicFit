@extends('layouts.app')

@section('required-styles-for-this-page')
    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
@stop

@section('page-title')
    Add Administrator 
@stop()

@section('content')
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->
   
   {!! Form::open(['url' => '', 'role' => 'form', 'id' =>'adminForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
    {!! Form::hidden('adminId', null) !!}
        @include('Settings.Admin.form')

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-primary btn-wide pull-right savebtn" >
                    <i class="fa fa-plus"></i> Add Administrator
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!} 

@stop

@section('required-script-for-this-page')
    
    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
    <!-- end: Country Code Selector -->
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
    <!-- end: JCrop -->   

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}

    {!! Html::script('assets/js/jquery-ui.min.js') !!}
    
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!} 
    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/admin.js?v='.time()) !!}

@stop