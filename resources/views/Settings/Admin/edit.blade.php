@extends('layouts.app')

@section('required-styles-for-this-page')
    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
    <!-- end: JCrop -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
@stop

@section('page-title')
	Edit Administrator 
@stop()

@section('content')
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->
    
	{!! Form::open(['url' => '', 'role' => 'form', 'id' =>'adminForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
        @include('Settings.Admin.form' , array('data' => $admin) )
        {!! Form::hidden('adminId', $admin->id , ['class' => 'no-clear']) !!}
    
    <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-wide pull-right savebtn editMode" id="adminbtn">
                            <i class="fa fa-edit"></i> Update Administrator
                        </button>
                    </div>
                </div>
    </div>
    {!! Form::close() !!} 

@stop

@section('required-script-for-this-page')
    
    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
    <!-- end: Country Code Selector -->
    
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
    <!-- end: JCrop -->   
    
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    {!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!}
    
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!} 

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/admin.js?v='.time()) !!}
@stop