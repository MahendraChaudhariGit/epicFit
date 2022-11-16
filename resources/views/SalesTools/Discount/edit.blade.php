@extends('layouts.app')

@section('required-styles-for-this-page')
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
@stop

@section('page-title')
	Edit Discount 
@stop()

@section('content')

	{!! Form::open(['url' => '', 'role' => 'form', 'id' =>'discountForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
        @include('SalesTools.Discount.form' , array('data' => $discount) )
        {!! Form::hidden('discountId', $discount->std_id , ['class' => 'no-clear']) !!}
    
    <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-wide pull-right savebtn" id="discountbtn">
                            <i class="fa fa-edit"></i> Update Discount
                        </button>
                    </div>
                </div>
    </div>
    {!! Form::close() !!} 


@stop

@section('required-script-for-this-page')
    
    {!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}

    <!-- start: Moment Library -->
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!} -->
    <!-- end: Moment Library -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/discount.js?v='.time()) !!}


@stop