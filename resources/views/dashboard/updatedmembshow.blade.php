@extends('layouts.app')
@section('required-styles-for-this-page')
{!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
{!! Html::style('assets/plugins/datepicker/css/datepicker.css?v='.time()) !!}
<!-- start: Bootstrap Select Master -->
{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Sweet alert css -->
{!! Html::style('vendor/sweetalert/sweet-alert.css?v='.time()) !!}
<!-- end: Sweet alert css -->

<!-- Start: NEW timepicker css -->  
{!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop()
@section('page-title')
<!-- start: Dashboard Title -->
<div class="row">
    <div class="col-sm-7">
        <h1 class="mainTitle">Updated Mebership Clients</h1>
    </div>
</div>
@stop
@section('content')
<div class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-8">
            @if(!empty($response))
            @foreach($response as $key => $value)
            <div class="panel panel-white no-radius">
                <div class="row panel-heading border-bottom">
                    <h4 class="col-md-4 panel-title"> {{ isset($value['clientName']) ? $value['clientName']: '' }} ({{ $key }})</h4>
                    <h4 class="col-md-8 panel-title"> {{ isset($value['message']) ? $value['message'] : '' }} </h4>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div> 
@stop
