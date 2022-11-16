
@extends('Result.masters.app')

@section('page-title')
<span >Update Food</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 

{!! Html::style('result/css/custom.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')

<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
     <div>

    <div class="row swMain">
        @include('mealplanner.food.form')
    </div>
    </div>
    </div>

@endsection

@section('script')
<!-- start: Summernote -->
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
<!-- end: Summernote -->
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}


{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}

<!-- start: Bootstrap Typeahead -->
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
{!! Html::script('assets/js/meal-planner.js?v='.time()) !!}
<!-- end: Bootstrap Typeahead -->
{!! Html::script('result/js/helper.js?v='.time()) !!}

@stop