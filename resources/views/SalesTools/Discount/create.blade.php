@extends('layouts.app')

@section('required-styles-for-this-page')
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
@stop

@section('page-title')
	Add Discount 
@stop()

@section('content')
    {!! Form::open(['url' => '', 'role' => 'form', 'id' =>'discountForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
    {!! Form::hidden('discountId', null) !!}
        @include('SalesTools.Discount.form')

    <div class="row">
        <div class="col-sm-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-wide pull-right savebtn" >
                            <i class="fa fa-plus"></i> Add Discount
                        </button>
                	</div>
        </div>
    </div>
    {!! Form::close() !!} 

@stop

@section('required-script-for-this-page')
    
    {!! Html::script('assets/js/jquery-ui.min.js') !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}

    <!-- start: Moment Library -->
   <!--  {!! Html::script('vendor/moment/moment.min.js') !!} -->
    <!-- end: Moment Library -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/discount.js?v='.time()) !!}


@stop