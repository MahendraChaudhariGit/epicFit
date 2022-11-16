@extends('layouts.app')

@section('required-styles-for-this-page')

    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}

    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css?v='.time()) !!}
@stop

@section('page-title')
    Edit Close Date 
@stop()

@section('content')

    {!! Form::open(['url' => 'Settings/closeddate/closeddate','id'=>'closeddateform','class' => 'container-fluid container-fullw bg-white scrollToTop']) !!}

    @include('Settings.closeddate.form', array('data' => $closedDate))
    {!! Form::hidden('closedDateId', $closedDate->cd_id , ['class' => 'no-clear']) !!}


    <div class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-primary btn-wide closedatebtn">
                <i class="fa fa-edit"></i> Update Closed Date
            </button>
        </div>
    </div>

    {!! Form::close() !!}
@endsection

@section('required-script-for-this-page')
    
    {!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!} 

    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js') !!}  -->

    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/closeddate.js?v='.time()) !!}

@stop