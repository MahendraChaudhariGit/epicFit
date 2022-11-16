@extends('Result.masters.app')

@section('page-title')
    <span >Weight And Date </span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css?v='.time()) !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css?v='.time()) !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css?v='.time()) !!}
{!! Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css?v='.time()) !!}
{!! Html::style('result/plugins/nestable-cliptwo/jquery.nestable.css?v='.time()) !!}


{!! Html::style('result/plugins/sweetalert/sweet-alert.css?v='.time()) !!}

{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}


{!! Html::style('result/css/custom.css?v='.time()) !!}

<style>
    .swMain.wizard-headding-style > ul{position:static;margin-bottom:25px}
    .wizard-headding-style .control-label{text-align:left}
</style>
<style type="text/css">
    .pac-container{
        z-index: 9999;
    }
    input.form-control.custom-width{
        margin-left: 0px;
        width:100%;
    }
</style>

<!-- VpForm -->
{!! Html::style('result/vendor/vp-form/css/vp-form.css?v='.time()) !!}
@stop

@section('angular-scripts-required')
    <!-- start: VpForm -->
    {!! Html::script('result/vendor/vp-form/js/jquery.windows.js?v='.time()) !!}
    {!! Html::script('result/vendor/vp-form/js/angular.js?v='.time()) !!}
    {!! Html::script('result/vendor/vp-form/js/autogrow.js?v='.time()) !!}
    {!! Html::script('result/vendor/vp-form/js/vp-form-parq.js?v='.time()) !!}
    <!-- end: VpForm -->
@stop

@section('content')

<!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->

<style>
body, html {
  height: 100%;
  margin: 0;
}

.bgimg {
  height: 100%;
  background-position: center;
  background-size: cover;
  position: relative;
  color: #111111;
  font-family: "Courier New", Courier, monospace;
  font-size: 25px;
}

.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
}

.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

hr {
  margin: auto;
  width: 40%;
}
</style>

  <div class="middle">
    <h1>Be Smarter <span>COMING SOON</span></h1>
    
    <hr>
  </div>
  


