@extends('layouts.app')

@section('required-styles-for-this-page')
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
@stop

@section('page-title')
    Add Resource
@stop()

@section('content')
  {!! Form::open(['url' => '', 'role' => 'form','id' =>'resourceForm', 'class' => 'container-fluid container-fullw bg-white scrollToTop']) !!}
  {!! Form::hidden('resourceId', null) !!}
    @include('Settings.resource.form')

         <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-primary btn-wide submitresource">
                        <i class="fa fa-plus"></i> Add Resource
                    </button>
                </div>
        </div>      

{!! Form::close() !!} 
@endsection


@section('required-script-for-this-page')
    
    {!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/resource.js?v='.time()) !!}

@stop