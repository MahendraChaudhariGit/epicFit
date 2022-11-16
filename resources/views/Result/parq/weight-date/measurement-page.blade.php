@extends('Result.masters.app')
@section('page-title')
    <span >Measurements</span> 
@stop
@section('required-styles')
<style type="text/css">
	.container-fullw{
		min-height: 290px;
	}
    .btn-info{
        background-color: #f64c1e !important;
        border-color: #f64c1e !important; 
    }
</style>
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css?v='.time()) !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css?v='.time()) !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css?v='.time()) !!}
{!! Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css?v='.time()) !!}
{!! Html::style('result/plugins/nestable-cliptwo/jquery.nestable.css?v='.time()) !!}


<!-- {!! Html::style('result/plugins/sweetalert/sweet-alert.css?v='.time()) !!} -->

{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}


{!! Html::style('result/css/custom.css?v='.time()) !!}



<!-- VpForm -->
{{-- {!! Html::style('result/vendor/vp-form/css/vp-form.css?v='.time()) !!} --}}
@stop
@section('content')
<div id="waitingShield" class="text-center waitingShield" data-slug="" style="display: none;">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
@php
    $moveSteps    = App\MovementStepSetup::where('mss_client_id', Auth::user()->account_id)->pluck('mss_step_name')->first();
    $movementStep = [];
    if ($moveSteps) {
        $movementStep = json_decode($moveSteps);
    }
    // dd($movementStep);

    $get_menus = App\ClientMenu::where('client_id',Auth::user()->account_id)->first();
@endphp
@include('Result.movement.movement_modal')
@include('Result.movement.movement_step_setup_modal',['movementStep'=>$movementStep,'client_id'=>Auth::user()->account_id])
<div class="row container-fullw">
	<div class="">
    <a href="{{ url('epic/WeightAndDate/Measurements') }}" class="btn btn-info m-b-10">
        Gallery
    </a>
    <a href="{{ url('/sleep') }}" class="btn btn-info m-b-10">
        Sleep form
    </a>
    <a href="{{ url('/chronotype-survey') }}" class="btn btn-info m-b-10">
        Chronotype Survey
    </a>
    
    @if(isset($get_menus) && !empty($get_menus->menues))
        @if(in_array("posture", explode(',',$get_menus->menues)))
            <a href="{{ url('/posture/lists') }}" class="btn btn-info m-b-10">
                Posture Analysis
            </a>
        @endif
    @endif
    @if(isset($get_menus) && !empty($get_menus->menues))
        @if(in_array("movement", explode(',',$get_menus->menues)))
    <button class="btn btn-info m-b-10" data-toggle="modal" data-target="#movementStepSetupModal">Movement</button>
    @endif
    @endif
    {{-- <a href="javascript:void(0)" class="btn btn-info m-b-10">
        Movement
    </a> --}}
</div>
</div>

@endsection

@section('custom-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://parsleyjs.org/dist/parsley.js"></script>
{!! Html::script('assets/plugins/web-rtc/record-rtc.js') !!}
<script src="https://www.webrtc-experiment.com/DetectRTC.js"> </script>
{!! Html::script('assets/plugins/web-rtc/common.js') !!}
{!! Html::script('result/js/form-wizard-movement.js?v='.time()) !!}
{!! Html::script('result/js/movement.js?v='.time()) !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}


@stop